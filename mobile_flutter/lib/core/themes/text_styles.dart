import 'package:flutter/material.dart';
import 'color_palette.dart';

class TextStyles {
  static const String fontFamily = 'Roboto';
  
  static const TextStyle headlineLarge = TextStyle(
    fontSize: 32, fontWeight: FontWeight.bold, color: ColorPalette.textPrimary,
  );
  static const TextStyle headlineMedium = TextStyle(
    fontSize: 28, fontWeight: FontWeight.bold, color: ColorPalette.textPrimary,
  );
  static const TextStyle headlineSmall = TextStyle(
    fontSize: 24, fontWeight: FontWeight.bold, color: ColorPalette.textPrimary,
  );
  
  static const TextStyle titleLarge = TextStyle(
    fontSize: 20, fontWeight: FontWeight.w600, color: ColorPalette.textPrimary,
  );
  static const TextStyle titleMedium = TextStyle(
    fontSize: 18, fontWeight: FontWeight.w600, color: ColorPalette.textPrimary,
  );
  static const TextStyle titleSmall = TextStyle(
    fontSize: 16, fontWeight: FontWeight.w600, color: ColorPalette.textPrimary,
  );
  
  static const TextStyle bodyLarge = TextStyle(
    fontSize: 16, fontWeight: FontWeight.normal, color: ColorPalette.textPrimary,
  );
  static const TextStyle bodyMedium = TextStyle(
    fontSize: 14, fontWeight: FontWeight.normal, color: ColorPalette.textPrimary,
  );
  static const TextStyle bodySmall = TextStyle(
    fontSize: 12, fontWeight: FontWeight.normal, color: ColorPalette.textSecondary,
  );
  
  static const TextStyle labelLarge = TextStyle(
    fontSize: 14, fontWeight: FontWeight.w500, color: ColorPalette.textSecondary,
  );
  static const TextStyle labelMedium = TextStyle(
    fontSize: 12, fontWeight: FontWeight.w500, color: ColorPalette.textSecondary,
  );
  
  static const TextStyle buttonText = TextStyle(
    fontSize: 16, fontWeight: FontWeight.w600, color: Colors.white,
  );
  
  static const TextStyle caption = TextStyle(
    fontSize: 12, fontWeight: FontWeight.normal, color: ColorPalette.textDisabled,
  );
  
  static const TextStyle overline = TextStyle(
    fontSize: 10, fontWeight: FontWeight.w500, letterSpacing: 1.5,
    color: ColorPalette.textSecondary,
  );
}