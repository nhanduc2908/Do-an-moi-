import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/constants/storage_keys.dart';
import '../../core/utils/secure_storage.dart';

class LanguageNotifier extends StateNotifier<Locale> {
  LanguageNotifier() : super(const Locale('en')) {
    _loadLanguage();
  }

  Future<void> _loadLanguage() async {
    final saved = await SecureStorage.read(StorageKeys.language);
    if (saved != null && saved.isNotEmpty) {
      state = Locale(saved);
    }
  }

  Future<void> setLanguage(Locale locale) async {
    state = locale;
    await SecureStorage.write(StorageKeys.language, locale.languageCode);
  }
}

final languageProvider = StateNotifierProvider<LanguageNotifier, Locale>((ref) {
  return LanguageNotifier();
});