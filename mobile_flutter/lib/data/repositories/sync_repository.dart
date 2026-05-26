import '../models/sync_queue_model.dart';
import '../models/api_response_model.dart';
import '../datasources/remote/sync_api.dart';
import '../datasources/local/sync_database.dart';
import '../../core/utils/logger.dart';

class SyncRepository {
  final SyncApi _syncApi;
  final SyncDatabase _syncDatabase;

  SyncRepository(this._syncApi, this._syncDatabase);

  Future<ApiResponseModel<void>> syncToFlutter({
    String? lastSyncTime,
    List<String>? entityTypes,
  }) async {
    try {
      return await _syncApi.syncToFlutter(lastSyncTime: lastSyncTime, entityTypes: entityTypes);
    } catch (e) {
      Logger.error('Sync to Flutter error', e);
      return ApiResponseModel(success: false, message: 'Failed to sync data');
    }
  }

  Future<ApiResponseModel<void>> syncToFirebase(Map<String, dynamic> data) async {
    try {
      return await _syncApi.syncToFirebase(data);
    } catch (e) {
      Logger.error('Sync to Firebase error', e);
      return ApiResponseModel(success: false, message: 'Failed to sync to Firebase');
    }
  }

  Future<ApiResponseModel<dynamic>> getSyncStatus() async {
    try {
      return await _syncApi.getSyncStatus();
    } catch (e) {
      Logger.error('Get sync status error', e);
      return ApiResponseModel(success: false, message: 'Failed to get sync status');
    }
  }

  Future<ApiResponseModel<List<SyncQueueModel>>> getSyncLogs({
    int page = 1,
    int limit = 20,
    String? status,
    String? type,
  }) async {
    try {
      return await _syncApi.getSyncLogs(page: page, limit: limit, status: status, type: type);
    } catch (e) {
      Logger.error('Get sync logs error', e);
      return ApiResponseModel(success: false, message: 'Failed to get sync logs');
    }
  }

  Future<void> addToSyncQueue(SyncQueueModel item) async {
    await _syncDatabase.insertSyncQueue(item);
  }

  Future<List<SyncQueueModel>> getPendingSyncItems() async {
    return await _syncDatabase.getPendingSyncItems();
  }

  Future<void> updateSyncStatus(String id, String status, {String? error}) async {
    await _syncDatabase.updateSyncStatus(id, status, error: error);
  }

  Future<void> clearOldSyncLogs(int days) async {
    await _syncDatabase.clearOldSyncLogs(days);
  }
}