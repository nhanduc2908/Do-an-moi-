import 'package:hive_flutter/hive_flutter.dart';
import '../../../core/constants/storage_keys.dart';
import '../../../core/utils/logger.dart';

class MigrationHelper {
  static const int currentVersion = 1;

  Future<void> migrate() async {
    final storedVersion = await _getStoredVersion();
    
    if (storedVersion < currentVersion) {
      Logger.info('Starting database migration from v$storedVersion to v$currentVersion');
      
      for (int version = storedVersion + 1; version <= currentVersion; version++) {
        await _runMigration(version);
      }
      
      await _setStoredVersion(currentVersion);
      Logger.info('Database migration completed');
    }
  }

  Future<int> _getStoredVersion() async {
    final box = await Hive.openBox(StorageKeys.settingsBox);
    return box.get('db_version', defaultValue: 0);
  }

  Future<void> _setStoredVersion(int version) async {
    final box = await Hive.openBox(StorageKeys.settingsBox);
    await box.put('db_version', version);
  }

  Future<void> _runMigration(int version) async {
    switch (version) {
      case 1:
        await _migrationV1();
        break;
    }
  }

  Future<void> _migrationV1() async {
    Logger.info('Running migration v1');
    
    // Clear old cache
    final cacheBox = await Hive.openBox(StorageKeys.cacheBox);
    await cacheBox.clear();
    
    // Initialize new boxes
    await Hive.openBox(StorageKeys.offlineBox);
    await Hive.openBox(StorageKeys.syncQueueBox);
    
    Logger.info('Migration v1 completed');
  }

  Future<void> resetDatabase() async {
    Logger.warning('Resetting database...');
    
    final boxes = [
      StorageKeys.userBox,
      StorageKeys.settingsBox,
      StorageKeys.cacheBox,
      StorageKeys.syncQueueBox,
      StorageKeys.offlineBox,
    ];
    
    for (final boxName in boxes) {
      final box = await Hive.openBox(boxName);
      await box.clear();
    }
    
    await _setStoredVersion(0);
    Logger.info('Database reset completed');
  }
}