import 'package:flutter/material.dart';
import 'light_theme.dart';
import 'dark_theme.dart';

class AppTheme {
  static ThemeData lightTheme = LightTheme.theme;
  static ThemeData darkTheme = DarkTheme.theme;
  
  static ThemeMode getThemeMode(String? themeMode) {
    switch (themeMode) {
      case 'light': return ThemeMode.light;
      case 'dark': return ThemeMode.dark;
      default: return ThemeMode.system;
    }
  }
  
  static String getThemeModeString(ThemeMode mode) {
    switch (mode) {
      case ThemeMode.light: return 'light';
      case ThemeMode.dark: return 'dark';
      default: return 'system';
    }
  }
}