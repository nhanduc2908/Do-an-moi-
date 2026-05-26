class VersionConfig {
  static const String currentVersion = '1.0.0';
  static const int currentVersionCode = 1;
  static const String minimumSupportedVersion = '1.0.0';
  static const String latestVersion = '1.0.0';
  static const String apiVersion = 'v1';
  static const String minimumApiVersion = 'v1';
  
  static const Map<String, String> versionChanges = {
    '1.0.0': 'Initial release with core features',
  };
  
  static bool isVersionSupported(String version) {
    return version.compareTo(minimumSupportedVersion) >= 0;
  }
  
  static bool isVersionOutdated(String version) {
    return version.compareTo(currentVersion) < 0;
  }
  
  static bool needsUpdate(String version) {
    return version.compareTo(latestVersion) < 0;
  }
}