import '../../core/utils/secure_storage.dart';
import '../../core/constants/storage_keys.dart';

class ApiVersionManager {
  static const String currentVersion = 'v1';
  static const String minimumSupportedVersion = 'v1';

  Future<String> getActiveVersion() async {
    final savedVersion = await SecureStorage.read(StorageKeys.apiVersion);
    return savedVersion ?? currentVersion;
  }

  Future<void> setActiveVersion(String version) async {
    await SecureStorage.write(StorageKeys.apiVersion, version);
  }

  bool isVersionSupported(String version) {
    return version.compareTo(minimumSupportedVersion) >= 0;
  }

  Future<bool> checkVersionCompatibility() async {
    // Implement version check with server
    return true;
  }
}