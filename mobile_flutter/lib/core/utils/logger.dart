import 'package:flutter/foundation.dart';

class Logger {
  static bool isDebugMode = kDebugMode;
  static bool isLoggingEnabled = true;
  
  static void init() {
    if (isDebugMode) debugPrint('📱 Logger initialized');
  }
  
  static void info(String message) {
    if (isDebugMode && isLoggingEnabled) debugPrint('ℹ️ INFO: $message');
  }
  
  static void error(String message, [dynamic error, StackTrace? stackTrace]) {
    debugPrint('❌ ERROR: $message');
    if (error != null) debugPrint('Error: $error');
    if (stackTrace != null) debugPrint('Stack: $stackTrace');
  }
  
  static void warning(String message) {
    if (isDebugMode && isLoggingEnabled) debugPrint('⚠️ WARNING: $message');
  }
  
  static void debug(String message) {
    if (isDebugMode && isLoggingEnabled) debugPrint('🐛 DEBUG: $message');
  }
  
  static void api(String message) {
    if (isDebugMode && isLoggingEnabled) debugPrint('🌐 API: $message');
  }
  
  static void sync(String message) {
    if (isDebugMode && isLoggingEnabled) debugPrint('🔄 SYNC: $message');
  }
  
  static void auth(String message) {
    if (isDebugMode && isLoggingEnabled) debugPrint('🔐 AUTH: $message');
  }
}