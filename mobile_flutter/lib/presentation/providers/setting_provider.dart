import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/repositories/setting_repository.dart';

class SettingState {
  final Map<String, dynamic> settings;
  final Map<String, dynamic> notificationSettings;
  final Map<String, dynamic> securitySettings;
  final bool isLoading;
  final String? error;

  SettingState({
    this.settings = const {},
    this.notificationSettings = const {},
    this.securitySettings = const {},
    this.isLoading = false,
    this.error,
  });

  SettingState copyWith({
    Map<String, dynamic>? settings,
    Map<String, dynamic>? notificationSettings,
    Map<String, dynamic>? securitySettings,
    bool? isLoading,
    String? error,
  }) {
    return SettingState(
      settings: settings ?? this.settings,
      notificationSettings: notificationSettings ?? this.notificationSettings,
      securitySettings: securitySettings ?? this.securitySettings,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
    );
  }
}

class SettingNotifier extends StateNotifier<SettingState> {
  final SettingRepository _repository;

  SettingNotifier(this._repository) : super(SettingState());

  Future<void> loadSettings() async {
    state = state.copyWith(isLoading: true, error: null);
    
    final response = await _repository.getSettings();
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        settings: response.data,
        isLoading: false,
      );
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
    }
  }

  Future<void> loadNotificationSettings() async {
    final response = await _repository.getNotificationSettings();
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(notificationSettings: response.data);
    }
  }

  Future<void> loadSecuritySettings() async {
    final response = await _repository.getSecuritySettings();
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(securitySettings: response.data);
    }
  }

  Future<bool> updateSettings(Map<String, dynamic> settings) async {
    state = state.copyWith(isLoading: true);
    
    final response = await _repository.updateSettings(settings);
    
    state = state.copyWith(isLoading: false);
    
    if (response.isSuccess) {
      await loadSettings();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  Future<bool> updateNotificationSettings(Map<String, dynamic> settings) async {
    final response = await _repository.updateNotificationSettings(settings);
    
    if (response.isSuccess) {
      await loadNotificationSettings();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final settingProvider = StateNotifierProvider<SettingNotifier, SettingState>((ref) {
  final repository = SettingRepository();
  return SettingNotifier(repository);
});