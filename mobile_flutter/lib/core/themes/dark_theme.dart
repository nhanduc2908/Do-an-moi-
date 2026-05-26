import 'package:flutter/material.dart';
import 'color_palette.dart';
import 'text_styles.dart';

class DarkTheme {
  static ThemeData get theme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.dark,
      primaryColor: ColorPalette.primary,
      scaffoldBackgroundColor: ColorPalette.darkBackground,
      colorScheme: const ColorScheme.dark(
        primary: ColorPalette.primary,
        secondary: ColorPalette.secondary,
        error: ColorPalette.error,
        surface: ColorPalette.darkSurface,
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: ColorPalette.darkSurface,
        foregroundColor: ColorPalette.darkTextPrimary,
        elevation: 0,
        centerTitle: true,
      ),
      cardTheme: CardTheme(
        elevation: 2,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        color: ColorPalette.darkSurface,
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: ColorPalette.darkSurface,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: ColorPalette.darkBorder),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: ColorPalette.darkBorder),
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
          minimumSize: const Size(double.infinity, 48),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        ),
      ),
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: ColorPalette.darkSurface,
        selectedItemColor: ColorPalette.primary,
        unselectedItemColor: ColorPalette.darkTextSecondary,
        type: BottomNavigationBarType.fixed,
      ),
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