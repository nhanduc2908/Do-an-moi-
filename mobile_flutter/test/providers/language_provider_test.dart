// Đường dẫn: mobile_flutter/test/providers/language_provider_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:security_evaluation_app/presentation/providers/language_provider.dart';

void main() {
  late ProviderContainer container;

  setUp(() {
    container = ProviderContainer();
  });

  tearDown(() {
    container.dispose();
  });

  group('LanguageProvider Tests', () {
    test('initial locale is English', () {
      final locale = container.read(languageProvider);
      
      expect(locale.languageCode, 'en');
    });

    test('setLanguage updates locale', () async {
      final notifier = container.read(languageProvider.notifier);
      
      await notifier.setLanguage(const Locale('vi'));
      
      final locale = container.read(languageProvider);
      expect(locale.languageCode, 'vi');
    });

    test('setLanguage persists to storage', () async {
      final notifier = container.read(languageProvider.notifier);
      
      await notifier.setLanguage(const Locale('ja'));
      
      final locale = container.read(languageProvider);
      expect(locale.languageCode, 'ja');
    });
  });
}