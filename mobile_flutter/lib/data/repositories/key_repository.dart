import '../models/key_model.dart';
import '../models/api_response_model.dart';
import '../datasources/remote/key_api.dart';
import '../../../core/utils/logger.dart';

class KeyRepository {
  final KeyApi _keyApi;

  KeyRepository(this._keyApi);

  Future<ApiResponseModel<List<KeyModel>>> getKeys({
    int page = 1,
    int limit = 20,
    String? status,
    String? type,
  }) async {
    try {
      return await _keyApi.getKeys(page: page, limit: limit, status: status, type: type);
    } catch (e) {
      Logger.error('Get keys error', e);
      return ApiResponseModel(success: false, message: 'Failed to load keys');
    }
  }

  Future<ApiResponseModel<KeyModel>> getKeyDetail(String id) async {
    try {
      return await _keyApi.getKeyDetail(id);
    } catch (e) {
      Logger.error('Get key detail error', e);
      return ApiResponseModel(success: false, message: 'Failed to load key details');
    }
  }

  Future<ApiResponseModel<KeyModel>> generateKey(Map<String, dynamic> data) async {
    try {
      return await _keyApi.generateKey(data);
    } catch (e) {
      Logger.error('Generate key error', e);
      return ApiResponseModel(success: false, message: 'Failed to generate key');
    }
  }

  Future<ApiResponseModel<bool>> verifyKey(String keyId, String signature) async {
    try {
      return await _keyApi.verifyKey(keyId, signature);
    } catch (e) {
      Logger.error('Verify key error', e);
      return ApiResponseModel(success: false, message: 'Failed to verify key');
    }
  }

  Future<ApiResponseModel<void>> revokeKey(String id, String reason) async {
    try {
      return await _keyApi.revokeKey(id, reason);
    } catch (e) {
      Logger.error('Revoke key error', e);
      return ApiResponseModel(success: false, message: 'Failed to revoke key');
    }
  }

  Future<ApiResponseModel<KeyModel>> rotateKey(String id) async {
    try {
      return await _keyApi.rotateKey(id);
    } catch (e) {
      Logger.error('Rotate key error', e);
      return ApiResponseModel(success: false, message: 'Failed to rotate key');
    }
  }

  Future<ApiResponseModel<List<dynamic>>> getKeyLogs(String id) async {
    try {
      return await _keyApi.getKeyLogs(id);
    } catch (e) {
      Logger.error('Get key logs error', e);
      return ApiResponseModel(success: false, message: 'Failed to load key logs');
    }
  }
}