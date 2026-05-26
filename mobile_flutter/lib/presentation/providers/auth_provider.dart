import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/models/user_model.dart';
import '../../data/repositories/auth_repository.dart';
import '../../core/utils/logger.dart';
import '../../services/auth/token_manager.dart';

class AuthState {
  final UserModel? user;
  final bool isLoading;
  final bool isAuthenticated;
  final String? error;

  AuthState({
    this.user,
    this.isLoading = false,
    this.isAuthenticated = false,
    this.error,
  });

  AuthState copyWith({
    UserModel? user,
    bool? isLoading,
    bool? isAuthenticated,
    String? error,
  }) {
    return AuthState(
      user: user ?? this.user,
      isLoading: isLoading ?? this.isLoading,
      isAuthenticated: isAuthenticated ?? this.isAuthenticated,
      error: error ?? this.error,
    );
  }
}

class AuthNotifier extends StateNotifier<AuthState> {
  final AuthRepository _authRepository;
  final TokenManager _tokenManager;

  AuthNotifier(this._authRepository, this._tokenManager) : super(AuthState()) {
    checkAuthStatus();
  }

  Future<void> checkAuthStatus() async {
    state = state.copyWith(isLoading: true);
    
    final token = await _tokenManager.getAccessToken();
    final userData = await _authRepository.getCurrentUser();
    
    if (token != null && userData != null) {
      state = state.copyWith(
        user: userData,
        isAuthenticated: true,
        isLoading: false,
      );
    } else {
      state = state.copyWith(
        isAuthenticated: false,
        isLoading: false,
      );
    }
  }

  Future<bool> login(String email, String password) async {
    state = state.copyWith(isLoading: true, error: null);
    
    final response = await _authRepository.login(email, password);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        user: response.data,
        isAuthenticated: true,
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

  Future<bool> register(Map<String, dynamic> data) async {
    state = state.copyWith(isLoading: true, error: null);
    
    final response = await _authRepository.register(data);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        user: response.data,
        isAuthenticated: true,
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

  Future<void> logout() async {
    state = state.copyWith(isLoading: true);
    
    await _authRepository.logout();
    await _tokenManager.clearTokens();
    
    state = AuthState();
  }

  Future<void> updateProfile(Map<String, dynamic> data) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _authRepository.updateProfile(data);
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        user: response.data,
        isLoading: false,
      );
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
    }
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  final authRepository = ref.watch(authRepositoryProvider);
  final tokenManager = TokenManager();
  return AuthNotifier(authRepository, tokenManager);
});

final authRepositoryProvider = Provider((ref) => AuthRepository());