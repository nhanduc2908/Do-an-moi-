import 'package:flutter/material.dart';

class AppConstants {
  static const String appName = 'Security Assessment Platform';
  static const String appVersion = '1.0.0';
  static const String appBuildNumber = '1';
  
  static const List<Locale> supportedLocales = [
    Locale('en', ''),
    Locale('vi', ''),
    Locale('ja', ''),
    Locale('ko', ''),
    Locale('zh', ''),
  ];
  
  static const Map<String, String> localeNames = {
    'en': 'English',
    'vi': 'Tiếng Việt',
    'ja': '日本語',
    'ko': '한국어',
    'zh': '中文',
  };
  
  static const int defaultPageSize = 20;
  static const int maxPageSize = 100;
  static const Duration cacheDuration = Duration(days: 7);
  static const Duration shortCacheDuration = Duration(hours: 1);
  static const Duration syncInterval = Duration(minutes: 15);
  static const int maxSyncRetries = 3;
  static const int maxFileSizeMB = 10;
  static const List<String> allowedFileTypes = ['pdf', 'jpg', 'png', 'doc', 'docx'];
  static const int maxLoginAttempts = 5;
  static const int lockoutDurationMinutes = 15;
  static const int passwordMinLength = 8;
  static const int otpExpirySeconds = 60;
  static const Duration shortAnimation = Duration(milliseconds: 200);
  static const Duration mediumAnimation = Duration(milliseconds: 400);
  static const Duration longAnimation = Duration(milliseconds: 600);
}