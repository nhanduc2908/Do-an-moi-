import '../models/notification_model.dart';
import '../models/api_response_model.dart';
import '../../../core/constants/api_constants.dart';
import '../datasources/remote/api_client.dart';
import '../../../core/utils/logger.dart';

class NotificationRepository {
  final ApiClient _apiClient;

  NotificationRepository(this._apiClient);

  Future<ApiResponseModel<List<NotificationModel>>> getNotifications({
    int page = 1,
    int limit = 20,
    bool? unreadOnly,
  }) async {
    try {
      final response = await _apiClient.get(
        ApiConstants.notifications,
        queryParams: {
          'page': page,
          'limit': limit,
          if (unreadOnly == true) 'unread': true,
        },
      );
      
      if (response['success'] == true) {
        final List<dynamic> data = response['data']['items'] ?? [];
        final notifications = data.map((item) => NotificationModel.fromJson(item)).toList();
        return ApiResponseModel(
          success: true,
          data: notifications,
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to load notifications',
      );
    } catch (e) {
      Logger.error('Get notifications error', e);
      return ApiResponseModel(success: false, message: 'Failed to load notifications');
    }
  }

  Future<ApiResponseModel<NotificationModel>> markAsRead(String id) async {
    try {
      final response = await _apiClient.post('${ApiConstants.notifications}/$id/read');
      
      if (response['success'] == true) {
        final notification = NotificationModel.fromJson(response['data']);
        return ApiResponseModel(
          success: true,
          data: notification,
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to mark notification as read',
      );
    } catch (e) {
      Logger.error('Mark as read error', e);
      return ApiResponseModel(success: false, message: 'Failed to mark as read');
    }
  }

  Future<ApiResponseModel<void>> markAllAsRead() async {
    try {
      final response = await _apiClient.post('${ApiConstants.notifications}/read-all');
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Mark all as read error', e);
      return ApiResponseModel(success: false, message: 'Failed to mark all as read');
    }
  }

  Future<ApiResponseModel<void>> deleteNotification(String id) async {
    try {
      final response = await _apiClient.delete('${ApiConstants.notifications}/$id');
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Delete notification error', e);
      return ApiResponseModel(success: false, message: 'Failed to delete notification');
    }
  }

  Future<ApiResponseModel<int>> getUnreadCount() async {
    try {
      final response = await _apiClient.get('${ApiConstants.notifications}/unread-count');
      
      if (response['success'] == true) {
        return ApiResponseModel(
          success: true,
          data: response['data']['count'],
          message: response['message'],
        );
      }
      
      return ApiResponseModel(
        success: false,
        message: response['message'] ?? 'Failed to get unread count',
      );
    } catch (e) {
      Logger.error('Get unread count error', e);
      return ApiResponseModel(success: false, message: 'Failed to get unread count');
    }
  }

  Future<ApiResponseModel<void>> registerToken(String token) async {
    try {
      final response = await _apiClient.post('${ApiConstants.notifications}/register-token', data: {
        'token': token,
        'platform': 'flutter',
      });
      
      return ApiResponseModel(
        success: response['success'] == true,
        message: response['message'],
      );
    } catch (e) {
      Logger.error('Register token error', e);
      return ApiResponseModel(success: false, message: 'Failed to register token');
    }
  }
}