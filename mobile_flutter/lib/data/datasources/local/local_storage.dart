import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/utils/logger.dart';

class LocalStorage {
  static SharedPreferences? _preferences;

  static Future<void> init() async {
    _preferences = await SharedPreferences.getInstance();
    Logger.info('LocalStorage initialized');
  }

  Future<void> write(String key, String value) async {
    await _preferences?.setString(key, value);
    Logger.debug('Written to SharedPreferences: $key');
  }

  Future<String?> read(String key) async {
    return _preferences?.getString(key);
  }

  Future<void> delete(String key) async {
    await _preferences?.remove(key);
    Logger.debug('Deleted from SharedPreferences: $key');
  }

  Future<void> deleteAll() async {
    await _preferences?.clear();
    Logger.debug('Cleared all SharedPreferences');
  }

  Future<bool> containsKey(String key) async {
    return _preferences?.containsKey(key) ?? false;
  }

  Future<void> writeBool(String key, bool value) async {
    await _preferences?.setBool(key, value);
  }

  Future<bool?> readBool(String key) async {
    return _preferences?.getBool(key);
  }

  Future<void> writeInt(String key, int value) async {
    await _preferences?.setInt(key, value);
  }

  Future<int?> readInt(String key) async {
    return _preferences?.getInt(key);
  }

  Future<void> writeDouble(String key, double value) async {
    await _preferences?.setDouble(key, value);
  }

  Future<double?> readDouble(String key) async {
    return _preferences?.getDouble(key);
  }

  Future<void> writeStringList(String key, List<String> value) async {
    await _preferences?.setStringList(key, value);
  }

  Future<List<String>?> readStringList(String key) async {
    return _preferences?.getStringList(key);
  }
}