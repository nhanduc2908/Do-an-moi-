// Đường dẫn: mobile_flutter/test/providers/theme_provider_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:security_evaluation_app/presentation/providers/theme_provider.dart';

void main() {
  late ProviderContainer container;

  setUp(() {
    container = ProviderContainer();
  });

  tearDown(() {
    container.dispose();
  });

  group('ThemeProvider Tests', () {
    test('initial theme mode is system', () {
      final mode = container.read(themeProvider);
      
      expect(mode, ThemeMode.system);
    });

    test('setTheme updates theme mode', () async {
      final notifier = container.read(themeProvider.notifier);
      
      await notifier.setTheme(ThemeMode.dark);
      
      final mode = container.read(themeProvider);
      expect(mode, ThemeMode.dark);
    });

    test('setTheme persists to storage', () async {
      final notifier = container.read(themeProvider.notifier);
      
      await notifier.setTheme(ThemeMode.light);
      
      final mode = container.read(themeProvider);
      expect(mode, ThemeMode.light);
    });
  });
}