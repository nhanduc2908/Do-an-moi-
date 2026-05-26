import '../../data/datasources/local/cache_manager.dart';
import '../../core/utils/logger.dart';

class OfflineCache {
  final CacheManager _cacheManager;

  OfflineCache(this._cacheManager);

  Future<void> cacheData(String key, dynamic data, {Duration? ttl}) async {
    await _cacheManager.set(key, data, ttl: ttl);
    Logger.offline('Cached data: $key');
  }

  Future<dynamic> getCachedData(String key) async {
    return await _cacheManager.get(key);
  }

  Future<void> clearCache() async {
    await _cacheManager.clear();
  }

  Future<bool> hasCache(String key) async {
    return await _cacheManager.has(key);
  }
}