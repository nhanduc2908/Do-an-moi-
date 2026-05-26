import '../../core/utils/secure_storage.dart';
import '../../core/constants/storage_keys.dart';
import '../../core/utils/logger.dart';

class SessionManager {
  Future<void> startSession(String userId) async {
    await SecureStorage.write(StorageKeys.userId, userId);
    await SecureStorage.write(StorageKeys.sessionStartTime, DateTime.now().toIso8601String());
    Logger.auth('Session started for user: $userId');
  }

  Future<void> endSession() async {
    await SecureStorage.delete(StorageKeys.userId);
    await SecureStorage.delete(StorageKeys.sessionStartTime);
    Logger.auth('Session ended');
  }

  Future<String?> getCurrentUserId() async {
    return await SecureStorage.read(StorageKeys.userId);
  }

  Future<Duration?> getSessionDuration() async {
    final startTime = await SecureStorage.read(StorageKeys.sessionStartTime);
    if (startTime == null) return null;
    final start = DateTime.parse(startTime);
    return DateTime.now().difference(start);
  }

  Future<bool> isSessionActive() async {
    final userId = await getCurrentUserId();
    return userId != null;
  }
}