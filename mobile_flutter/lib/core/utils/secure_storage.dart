import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'logger.dart';

class SecureStorage {
  static const FlutterSecureStorage _storage = FlutterSecureStorage(
    aOptions: AndroidOptions(encryptedSharedPreferences: true),
    iosOptions: IOSOptions(accessibility: KeychainAccessibility.first_unlock),
  );
  
  static Future<void> init() async => Logger.info('SecureStorage initialized');
  
  static Future<void> write(String key, String value) async {
    await _storage.write(key: key, value: value);
    Logger.debug('Written to secure storage: $key');
  }
  
  static Future<String?> read(String key) async => await _storage.read(key: key);
  
  static Future<void> delete(String key) async {
    await _storage.delete(key: key);
    Logger.debug('Deleted from secure storage: $key');
  }
  
  static Future<void> deleteAll() async {
    await _storage.deleteAll();
    Logger.debug('Cleared all secure storage');
  }
  
  static Future<bool> containsKey(String key) async {
    return await _storage.read(key: key) != null;
  }
  
  static Future<Map<String, String>> readAll() async {
    return await _storage.readAll();
  }
}