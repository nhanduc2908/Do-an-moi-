import '../models/user_model.dart';
import '../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';
import '../datasources/remote/api_client.dart';
import '../../../core/utils/logger.dart';

class UserRepository {
  final ApiClient _apiClient;

  UserRepository(this._apiClient);

  Future<ApiResponseModel<List<UserModel>>> getUsers({
    int page = 1,
    int limit = 20,
    String? role,
    String? status,
    String? search,
  }) async {
    try {
      final response = await _apiClient.get(
        ApiConstants.users,
        queryParams: {
          'page': page,
          'limit': limit,
          if (role != null) 'role': role,
          if (status != null) 'status': status,
          if (search != null) 'search': search,
        },
      );
      
      if (response['success'] == true) {
        final List<dynamic> data = response['data']['items'] ?? [];
        final users = data.map((item) => UserModel.fromJson(item)).toList();
        return ApiResponseModel(
          success: true,
          data: users,
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to load users',
      );
    } catch (e) {
      Logger.error('Get users error', e);
      return ApiResponseModel(success: false, message: 'Failed to load users');
    }
  }

  Future<ApiResponseModel<UserModel>> getUserDetail(String id) async {
    try {
      final path = ApiConstants.userDetail.replaceAll('{id}', id);
      final response = await _apiClient.get(path);
      
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
        message: response['message'] ?? 'Failed to load user details',
      );
    } catch (e) {
      Logger.error('Get user detail error', e);
      return ApiResponseModel(success: false, message: 'Failed to load user details');
    }
  }

  Future<ApiResponseModel<UserModel>> createUser(Map<String, dynamic> data) async {
    try {
      final response = await _apiClient.post(ApiConstants.users, data: data);
      
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
        message: response['message'] ?? 'Failed to create user',
      );
    } catch (e) {
      Logger.error('Create user error', e);
      return ApiResponseModel(success: false, message: 'Failed to create user');
    }
  }

  Future<ApiResponseModel<UserModel>> updateUser(String id, Map<String, dynamic> data) async {
    try {
      final path = ApiConstants.userDetail.replaceAll('{id}', id);
      final response = await _apiClient.put(path, data: data);
      
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
        message: response['message'] ?? 'Failed to update user',
      );
    } catch (e) {
      Logger.error('Update user error', e);
      return ApiResponseModel(success: false, message: 'Failed to update user');
    }
  }

  Future<ApiResponseModel<void>> deleteUser(String id) async {
    try {
      final path = ApiConstants.userDetail.replaceAll('{id}', id);
      final response = await _apiClient.delete(path);
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Delete user error', e);
      return ApiResponseModel(success: false, message: 'Failed to delete user');
    }
  }

  Future<ApiResponseModel<void>> suspendUser(String id, String reason) async {
    try {
      final path = '${ApiConstants.userDetail.replaceAll('{id}', id)}/suspend';
      final response = await _apiClient.post(path, data: {'reason': reason});
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Suspend user error', e);
      return ApiResponseModel(success: false, message: 'Failed to suspend user');
    }
  }

  Future<ApiResponseModel<void>> activateUser(String id) async {
    try {
      final path = '${ApiConstants.userDetail.replaceAll('{id}', id)}/activate';
      final response = await _apiClient.post(path);
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Activate user error', e);
      return ApiResponseModel(success: false, message: 'Failed to activate user');
    }
  }
}