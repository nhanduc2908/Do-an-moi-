import 'package:hive_flutter/hive_flutter.dart';
import '../../../core/constants/storage_keys.dart';
import '../../../core/utils/logger.dart';

class HiveService {
  static Future<void> init() async {
    await Hive.initFlutter();
    await Hive.openBox(StorageKeys.userBox);
    await Hive.openBox(StorageKeys.settingsBox);
    await Hive.openBox(StorageKeys.cacheBox);
    await Hive.openBox(StorageKeys.offlineBox);
    Logger.info('HiveService initialized');
  }

  Box<T> getBox<T>(String boxName) {
    return Hive.box<T>(boxName);
  }

  Future<void> writeToBox<T>(String boxName, String key, T value) async {
    final box = Hive.box<T>(boxName);
    await box.put(key, value);
    Logger.debug('Written to Hive box: $boxName -> $key');
  }

  Future<T?> readFromBox<T>(String boxName, String key) async {
    final box = Hive.box<T>(boxName);
    return box.get(key);
  }

  Future<void> deleteFromBox(String boxName, String key) async {
    final box = await Hive.openBox(boxName);
    await box.delete(key);
    Logger.debug('Deleted from Hive box: $boxName -> $key');
  }

  Future<void> clearBox(String boxName) async {
    final box = await Hive.openBox(boxName);
    await box.clear();
    Logger.debug('Cleared Hive box: $boxName');
  }

  List<T> getAllFromBox<T>(String boxName) {
    final box = Hive.box<T>(boxName);
    return box.values.toList();
  }
}