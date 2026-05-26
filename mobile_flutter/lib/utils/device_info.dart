// Đường dẫn: mobile_flutter/lib/utils/device_info.dart

import 'package:device_info_plus/device_info_plus.dart';
import 'package:flutter/foundation.dart';

class DeviceInfo {
  static final DeviceInfoPlugin _deviceInfo = DeviceInfoPlugin();

  static Future<Map<String, dynamic>> getDeviceInfo() async {
    if (kIsWeb) {
      return await _getWebInfo();
    } else if (defaultTargetPlatform == TargetPlatform.android) {
      return await _getAndroidInfo();
    } else if (defaultTargetPlatform == TargetPlatform.iOS) {
      return await _getIosInfo();
    }
    return {};
  }

  static Future<Map<String, dynamic>> _getAndroidInfo() async {
    final info = await _deviceInfo.androidInfo;
    return {
      'model': info.model,
      'version': info.version.release,
      'sdk': info.version.sdkInt,
      'manufacturer': info.manufacturer,
    };
  }

  static Future<Map<String, dynamic>> _getIosInfo() async {
    final info = await _deviceInfo.iosInfo;
    return {
      'model': info.model,
      'version': info.systemVersion,
      'name': info.name,
    };
  }

  static Future<Map<String, dynamic>> _getWebInfo() async {
    final info = await _deviceInfo.webBrowserInfo;
    return {
      'browser': info.browserName?.toString(),
      'version': info.browserVersion,
      'platform': info.platform,
    };
  }
}