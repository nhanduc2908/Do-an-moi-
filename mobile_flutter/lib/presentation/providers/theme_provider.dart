import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/constants/storage_keys.dart';
import '../../core/utils/secure_storage.dart';

class ThemeNotifier extends StateNotifier<ThemeMode> {
  ThemeNotifier() : super(ThemeMode.system) {
    _loadTheme();
  }

  Future<void> _loadTheme() async {
    final saved = await SecureStorage.read(StorageKeys.themeMode);
    if (saved != null) {
      state = ThemeMode.values[int.parse(saved)];
    }
  }

  Future<void> setTheme(ThemeMode mode) async {
    state = mode;
    await SecureStorage.write(StorageKeys.themeMode, mode.index.toString());
  }
}

final themeProvider = StateNotifierProvider<ThemeNotifier, ThemeMode>((ref) {
  return ThemeNotifier();
});