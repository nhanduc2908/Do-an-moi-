import '../api_client.dart';
import '../../models/user_model.dart';
import '../../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';

class AuthApi {
  final ApiClient _apiClient;

  AuthApi(this._apiClient);

  Future<ApiResponseModel<UserModel>> login(String email, String password) async {
    final response = await _apiClient.post(ApiConstants.login, data: {
      'email': email,
      'password': password,
    });
    
    if (response['success'] == true) {
      final user = UserModel.fromJson(response['data']['user']);
      return ApiResponseModel(
        success: true,
        data: user,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Login failed',
    );
  }

  Future<ApiResponseModel<UserModel>> register(Map<String, dynamic> data) async {
    final response = await _apiClient.post(ApiConstants.register, data: data);
    
    if (response['success'] == true) {
      final user = UserModel.fromJson(response['data']['user']);
      return ApiResponseModel(
        success: true,
        data: user,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Registration failed',
    );
  }

  Future<ApiResponseModel<void>> logout() async {
    final response = await _apiClient.post(ApiConstants.logout);
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<UserModel>> getProfile() async {
    final response = await _apiClient.get(ApiConstants.profile);
    
    if (response['success'] == true) {
      final user = UserModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: user,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to get profile',
    );
  }

  Future<ApiResponseModel<UserModel>> updateProfile(Map<String, dynamic> data) async {
    final response = await _apiClient.put(ApiConstants.updateProfile, data: data);
    
    if (response['success'] == true) {
      final user = UserModel.fromJson(response['data']);
      return ApiResponseModel(
        success: true,
        data: user,
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to update profile',
    );
  }

  Future<ApiResponseModel<void>> changePassword(String oldPassword, String newPassword) async {
    final response = await _apiClient.post(ApiConstants.changePassword, data: {
      'current_password': oldPassword,
      'new_password': newPassword,
    });
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> forgotPassword(String email) async {
    final response = await _apiClient.post(ApiConstants.forgotPassword, data: {'email': email});
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<void>> resetPassword(String token, String password) async {
    final response = await _apiClient.post(ApiConstants.resetPassword, data: {
      'token': token,
      'password': password,
    });
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }

  Future<ApiResponseModel<String>> verifyMfa(String code) async {
    final response = await _apiClient.post(ApiConstants.verifyMfa, data: {'code': code});
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data']['token'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Invalid MFA code',
    );
  }

  Future<ApiResponseModel<String>> enableMfa() async {
    final response = await _apiClient.post(ApiConstants.enableMfa);
    
    if (response['success'] == true) {
      return ApiResponseModel(
        success: true,
        data: response['data']['secret'],
        message: response['message'],
      );
    }
    
    return ApiResponseModel(
      success: false,
      message: response['message'] ?? 'Failed to enable MFA',
    );
  }

  Future<ApiResponseModel<void>> disableMfa() async {
    final response = await _apiClient.post(ApiConstants.disableMfa);
    
    return ApiResponseModel(
      success: response['success'] == true,
      message: response['message'],
    );
  }
}