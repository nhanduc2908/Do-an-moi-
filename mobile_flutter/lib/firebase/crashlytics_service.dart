// Đường dẫn: mobile_flutter/lib/firebase/crashlytics_service.dart

import 'package:firebase_crashlytics/firebase_crashlytics.dart';
import 'package:flutter/foundation.dart';

class CrashlyticsService {
  static Future<void> initialize() async {
    if (!kIsWeb) {
      FlutterError.onError = FirebaseCrashlytics.instance.recordFlutterFatalError;
      PlatformDispatcher.instance.onError = (error, stack) {
        FirebaseCrashlytics.instance.recordError(error, stack, fatal: true);
        return true;
      };
    }
  }

  static void log(String message) {
    FirebaseCrashlytics.instance.log(message);
  }

  static void recordError(dynamic error, StackTrace? stack) {
    FirebaseCrashlytics.instance.recordError(error, stack);
  }

  static void setCustomKey(String key, dynamic value) {
    FirebaseCrashlytics.instance.setCustomKey(key, value);
  }
}