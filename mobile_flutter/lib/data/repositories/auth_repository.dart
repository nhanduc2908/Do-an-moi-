import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/user_model.dart';
import '../models/api_response_model.dart';
import '../datasources/remote/auth_api.dart';
import '../datasources/local/local_storage.dart';
import '../../core/constants/storage_keys.dart';
import '../../core/utils/logger.dart';

class AuthRepository {
  final AuthApi _authApi;
  final LocalStorage _localStorage;
  
  AuthRepository(this._authApi, this._localStorage);
  
  Future<ApiResponseModel<UserModel>> login(String email, String password) async {
    try {
      final response = await _authApi.login(email, password);
      if (response.isSuccess && response.data != null) {
        await _saveUserData(response.data!);
        return response;
      }
      return response;
    } catch (e) {
      Logger.error('Login error', e);
      return ApiResponseModel(success: false, message: 'Login failed');
    }
  }
  
  Future<ApiResponseModel<UserModel>> register(Map<String, dynamic> data) async {
    try {
      final response = await _authApi.register(data);
      if (response.isSuccess && response.data != null) {
        await _saveUserData(response.data!);
        return response;
      }
      return response;
    } catch (e) {
      Logger.error('Register error', e);
      return ApiResponseModel(success: false, message: 'Registration failed');
    }
  }
  
  Future<ApiResponseModel<void>> logout() async {
    try {
      await _authApi.logout();
      await _clearUserData();
      return ApiResponseModel(success: true, message: 'Logged out successfully');
    } catch (e) {
      Logger.error('Logout error', e);
      await _clearUserData();
      return ApiResponseModel(success: true, message: 'Logged out');
    }
  }
  
  Future<ApiResponseModel<UserModel>> getProfile() async {
    try {
      final response = await _authApi.getProfile();
      if (response.isSuccess && response.data != null) {
        await _saveUserData(response.data!);
        return response;
      }
      return response;
    } catch (e) {
      Logger.error('Get profile error', e);
      return ApiResponseModel(success: false, message: 'Failed to get profile');
    }
  }
  
  Future<ApiResponseModel<UserModel>> updateProfile(Map<String, dynamic> data) async {
    try {
      final response = await _authApi.updateProfile(data);
      if (response.isSuccess && response.data != null) {
        await _saveUserData(response.data!);
        return response;
      }
      return response;
    } catch (e) {
      Logger.error('Update profile error', e);
      return ApiResponseModel(success: false, message: 'Failed to update profile');
    }
  }
  
  Future<ApiResponseModel<void>> changePassword(String oldPassword, String newPassword) async {
    try {
      return await _authApi.changePassword(oldPassword, newPassword);
    } catch (e) {
      Logger.error('Change password error', e);
      return ApiResponseModel(success: false, message: 'Failed to change password');
    }
  }
  
  Future<ApiResponseModel<void>> forgotPassword(String email) async {
    try {
      return await _authApi.forgotPassword(email);
    } catch (e) {
      Logger.error('Forgot password error', e);
      return ApiResponseModel(success: false, message: 'Failed to send reset link');
    }
  }
  
  Future<ApiResponseModel<void>> resetPassword(String token, String password) async {
    try {
      return await _authApi.resetPassword(token, password);
    } catch (e) {
      Logger.error('Reset password error', e);
      return ApiResponseModel(success: false, message: 'Failed to reset password');
    }
  }
  
  Future<ApiResponseModel<String>> verifyMfa(String code) async {
    try {
      return await _authApi.verifyMfa(code);
    } catch (e) {
      Logger.error('Verify MFA error', e);
      return ApiResponseModel(success: false, message: 'Invalid MFA code');
    }
  }
  
  Future<ApiResponseModel<String>> enableMfa() async {
    try {
      return await _authApi.enableMfa();
    } catch (e) {
      Logger.error('Enable MFA error', e);
      return ApiResponseModel(success: false, message: 'Failed to enable MFA');
    }
  }
  
  Future<ApiResponseModel<void>> disableMfa() async {
    try {
      return await _authApi.disableMfa();
    } catch (e) {
      Logger.error('Disable MFA error', e);
      return ApiResponseModel(success: false, message: 'Failed to disable MFA');
    }
  }
  
  Future<UserModel?> getCurrentUser() async {
    final userJson = await _localStorage.read(StorageKeys.userData);
    if (userJson != null) {
      try {
        return UserModel.fromJson(jsonDecode(userJson));
      } catch (e) {
        Logger.error('Parse user error', e);
      }
    }
    return null;
  }
  
  Future<String?> getAccessToken() async {
    return await _localStorage.read(StorageKeys.accessToken);
  }
  
  Future<void> _saveUserData(UserModel user) async {
    await _localStorage.write(StorageKeys.userData, jsonEncode(user.toJson()));
  }
  
  Future<void> _clearUserData() async {
    await _localStorage.delete(StorageKeys.userData);
    await _localStorage.delete(StorageKeys.accessToken);
    await _localStorage.delete(StorageKeys.refreshToken);
  }
}