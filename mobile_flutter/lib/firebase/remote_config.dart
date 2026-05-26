// Đường dẫn: mobile_flutter/lib/firebase/remote_config.dart

import 'package:firebase_remote_config/firebase_remote_config.dart';

class RemoteConfigService {
  final FirebaseRemoteConfig _remoteConfig = FirebaseRemoteConfig.instance;

  Future<void> initialize() async {
    await _remoteConfig.setConfigSettings(RemoteConfigSettings(
      fetchTimeout: const Duration(seconds: 10),
      minimumFetchInterval: const Duration(hours: 1),
    ));
    await _remoteConfig.fetchAndActivate();
  }

  String getString(String key) => _remoteConfig.getString(key);
  int getInt(String key) => _remoteConfig.getInt(key);
  bool getBool(String key) => _remoteConfig.getBool(key);
  double getDouble(String key) => _remoteConfig.getDouble(key);
}