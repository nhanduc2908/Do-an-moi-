import 'package:flutter/material.dart';
import 'color_palette.dart';
import 'text_styles.dart';

class LightTheme {
  static ThemeData get theme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.light,
      primaryColor: ColorPalette.primary,
      scaffoldBackgroundColor: ColorPalette.background,
      colorScheme: const ColorScheme.light(
        primary: ColorPalette.primary,
        secondary: ColorPalette.secondary,
        error: ColorPalette.error,
        surface: ColorPalette.surface,
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: ColorPalette.surface,
        foregroundColor: ColorPalette.textPrimary,
        elevation: 0,
        centerTitle: true,
        iconTheme: IconThemeData(color: ColorPalette.textPrimary),
      ),
      cardTheme: CardTheme(
        elevation: 2,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        color: ColorPalette.surface,
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: ColorPalette.surface,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: ColorPalette.border),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: ColorPalette.border),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: ColorPalette.primary, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: ColorPalette.error),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: ColorPalette.primary,
          foregroundColor: Colors.white,
          minimumSize: const Size(double.infinity, 48),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(foregroundColor: ColorPalette.primary),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: ColorPalette.primary,
          side: const BorderSide(color: ColorPalette.primary),
          minimumSize: const Size(double.infinity, 48),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        ),
      ),
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: ColorPalette.surface,
        selectedItemColor: ColorPalette.primary,
        unselectedItemColor: ColorPalette.textSecondary,
        type: BottomNavigationBarType.fixed,
        elevation: 8,
      ),
      dividerTheme: const DividerThemeData(color: ColorPalette.border, thickness: 1),
      textTheme: const TextTheme(
        headlineLarge: TextStyles.headlineLarge,
        headlineMedium: TextStyles.headlineMedium,
        headlineSmall: TextStyles.headlineSmall,
        titleLarge: TextStyles.titleLarge,
        titleMedium: TextStyles.titleMedium,
        titleSmall: TextStyles.titleSmall,
        bodyLarge: TextStyles.bodyLarge,
        bodyMedium: TextStyles.bodyMedium,
        bodySmall: TextStyles.bodySmall,
        labelLarge: TextStyles.labelLarge,
        labelMedium: TextStyles.labelMedium,
      ),
    );
  }
}