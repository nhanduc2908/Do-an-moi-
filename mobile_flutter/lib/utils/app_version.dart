// Đường dẫn: mobile_flutter/lib/utils/app_version.dart

import 'package:package_info_plus/package_info_plus.dart';

class AppVersion {
  static String version = '1.0.0';
  static String buildNumber = '1';
  static String appName = 'Security Assessment Platform';

  static Future<void> init() async {
    final packageInfo = await PackageInfo.fromPlatform();
    version = packageInfo.version;
    buildNumber = packageInfo.buildNumber;
    appName = packageInfo.appName;
  }

  static String getVersionString() => 'v$version ($buildNumber)';
  static String getFullVersion() => '$appName - Version $version (Build $buildNumber)';
}