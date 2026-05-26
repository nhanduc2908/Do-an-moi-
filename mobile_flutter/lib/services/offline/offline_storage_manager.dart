import '../../data/datasources/local/hive_service.dart';
import '../../core/utils/logger.dart';

class OfflineStorageManager {
  final HiveService _hiveService;

  OfflineStorageManager(this._hiveService);

  Future<void> saveData(String boxName, String key, dynamic value) async {
    await _hiveService.writeToBox(boxName, key, value);
    Logger.offline('Saved to offline storage: $boxName/$key');
  }

  Future<dynamic> getData(String boxName, String key) async {
    return await _hiveService.readFromBox(boxName, key);
  }

  Future<void> deleteData(String boxName, String key) async {
    await _hiveService.deleteFromBox(boxName, key);
  }

  Future<void> clearBox(String boxName) async {
    await _hiveService.clearBox(boxName);
  }
}