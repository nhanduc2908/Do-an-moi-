import '../../core/utils/secure_storage.dart';
import '../../core/constants/storage_keys.dart';
import '../../core/utils/logger.dart';

class TokenManager {
  Future<void> saveTokens(String accessToken, String refreshToken) async {
    await SecureStorage.write(StorageKeys.accessToken, accessToken);
    await SecureStorage.write(StorageKeys.refreshToken, refreshToken);
    Logger.auth('Tokens saved');
  }

  Future<String?> getAccessToken() async {
    return await SecureStorage.read(StorageKeys.accessToken);
  }

  Future<String?> getRefreshToken() async {
    return await SecureStorage.read(StorageKeys.refreshToken);
  }

  Future<void> clearTokens() async {
    await SecureStorage.delete(StorageKeys.accessToken);
    await SecureStorage.delete(StorageKeys.refreshToken);
    Logger.auth('Tokens cleared');
  }

  Future<bool> hasValidToken() async {
    final token = await getAccessToken();
    return token != null && token.isNotEmpty;
  }
}