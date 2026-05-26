import 'dart:convert';
import 'package:hive_flutter/hive_flutter.dart';
import '../../../core/constants/storage_keys.dart';
import '../../../core/utils/logger.dart';

class CacheManager {
  late Box<String> _cacheBox;

  Future<void> init() async {
    _cacheBox = await Hive.openBox<String>(StorageKeys.cacheBox);
    Logger.info('CacheManager initialized');
  }

  Future<void> set(String key, dynamic value, {Duration? ttl}) async {
    final cacheItem = {
      'data': value,
      'expiresAt': ttl != null ? DateTime.now().add(ttl).toIso8601String() : null,
    };
    await _cacheBox.put(key, jsonEncode(cacheItem));
    Logger.debug('Cached: $key');
  }

  Future<dynamic> get(String key) async {
    final cached = _cacheBox.get(key);
    if (cached == null) return null;
    
    try {
      final cacheItem = jsonDecode(cached);
      final expiresAt = cacheItem['expiresAt'];
      
      if (expiresAt != null && DateTime.parse(expiresAt).isBefore(DateTime.now())) {
        await _cacheBox.delete(key);
        Logger.debug('Cache expired: $key');
        return null;
      }
      
      return cacheItem['data'];
    } catch (e) {
      Logger.error('Cache get error for $key', e);
      return null;
    }
  }

  Future<void> delete(String key) async {
    await _cacheBox.delete(key);
    Logger.debug('Deleted cache: $key');
  }

  Future<void> clear() async {
    await _cacheBox.clear();
    Logger.debug('Cleared all cache');
  }

  Future<bool> has(String key) async {
    return _cacheBox.containsKey(key);
  }

  Future<List<String>> getAllKeys() async {
    return _cacheBox.keys.map((key) => key.toString()).toList();
  }
}