import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/models/user_model.dart';
import '../../data/repositories/user_repository.dart';

class UserState {
  final List<UserModel> users;
  final UserModel? selectedUser;
  final bool isLoading;
  final String? error;

  UserState({
    this.users = const [],
    this.selectedUser,
    this.isLoading = false,
    this.error,
  });

  UserState copyWith({
    List<UserModel>? users,
    UserModel? selectedUser,
    bool? isLoading,
    String? error,
  }) {
    return UserState(
      users: users ?? this.users,
      selectedUser: selectedUser ?? this.selectedUser,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
    );
  }
}

class UserNotifier extends StateNotifier<UserState> {
  final UserRepository _repository;

  UserNotifier(this._repository) : super(UserState());

  Future<void> loadUsers({String? role, String? status}) async {
    state = state.copyWith(isLoading: true, error: null);
    
    final response = await _repository.getUsers(role: role, status: status);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        users: response.data!,
        isLoading: false,
      );
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
    }
  }

  Future<UserModel?> loadUserDetail(String id) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.getUserDetail(id);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        selectedUser: response.data,
        isLoading: false,
      );
      return response.data;
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
      return null;
    }
  }

  Future<bool> createUser(Map<String, dynamic> data) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.createUser(data);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        users: [response.data!, ...state.users],
        isLoading: false,
      );
      return true;
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
      return false;
    }
  }

  Future<bool> updateUser(String id, Map<String, dynamic> data) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.updateUser(id, data);
    
    if (response.isSuccess) {
      await loadUsers();
      state = state.copyWith(isLoading: false);
      return true;
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
      return false;
    }
  }

  Future<bool> deleteUser(String id) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.deleteUser(id);
    
    state = state.copyWith(isLoading: false);
    
    if (response.isSuccess) {
      await loadUsers();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final userProvider = StateNotifierProvider<UserNotifier, UserState>((ref) {
  final repository = UserRepository();
  return UserNotifier(repository);
});