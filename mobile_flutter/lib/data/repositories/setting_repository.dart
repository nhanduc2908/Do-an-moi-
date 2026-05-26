import '../models/setting_model.dart';
import '../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';
import '../datasources/remote/api_client.dart';
import '../../../core/utils/logger.dart';

class SettingRepository {
  final ApiClient _apiClient;

  SettingRepository(this._apiClient);

  Future<ApiResponseModel<Map<String, dynamic>>> getSettings() async {
    try {
      final response = await _apiClient.get(ApiConstants.settings);
      
      if (response['success'] == true) {
        return ApiResponseModel(
          success: true,
          data: response['data'],
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to load settings',
      );
    } catch (e) {
      Logger.error('Get settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to load settings');
    }
  }

  Future<ApiResponseModel<void>> updateSettings(Map<String, dynamic> settings) async {
    try {
      final response = await _apiClient.put(ApiConstants.settings, data: settings);
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Update settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to update settings');
    }
  }

  Future<ApiResponseModel<Map<String, dynamic>>> getNotificationSettings() async {
    try {
      final response = await _apiClient.get('${ApiConstants.settings}/notifications');
      
      if (response['success'] == true) {
        return ApiResponseModel(
          success: true,
          data: response['data'],
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to load notification settings',
      );
    } catch (e) {
      Logger.error('Get notification settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to load notification settings');
    }
  }

  Future<ApiResponseModel<void>> updateNotificationSettings(Map<String, dynamic> settings) async {
    try {
      final response = await _apiClient.put('${ApiConstants.settings}/notifications', data: settings);
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Update notification settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to update notification settings');
    }
  }

  Future<ApiResponseModel<Map<String, dynamic>>> getSecuritySettings() async {
    try {
      final response = await _apiClient.get('${ApiConstants.settings}/security');
      
      if (response['success'] == true) {
        return ApiResponseModel(
          success: true,
          data: response['data'],
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to load security settings',
      );
    } catch (e) {
      Logger.error('Get security settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to load security settings');
    }
  }

  Future<ApiResponseModel<void>> updateSecuritySettings(Map<String, dynamic> settings) async {
    try {
      final response = await _apiClient.put('${ApiConstants.settings}/security', data: settings);
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Update security settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to update security settings');
    }
  }

  Future<ApiResponseModel<Map<String, dynamic>>> getBackupSettings() async {
    try {
      final response = await _apiClient.get('${ApiConstants.settings}/backup');
      
      if (response['success'] == true) {
        return ApiResponseModel(
          success: true,
          data: response['data'],
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to load backup settings',
      );
    } catch (e) {
      Logger.error('Get backup settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to load backup settings');
    }
  }

  Future<ApiResponseModel<void>> updateBackupSettings(Map<String, dynamic> settings) async {
    try {
      final response = await _apiClient.put('${ApiConstants.settings}/backup', data: settings);
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Update backup settings error', e);
      return ApiResponseModel(success: false, message: 'Failed to update backup settings');
    }
  }
}