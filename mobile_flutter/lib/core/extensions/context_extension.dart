import 'package:flutter/material.dart';

extension ContextExtension on BuildContext {
  ThemeData get theme => Theme.of(this);
  TextTheme get textTheme => Theme.of(this).textTheme;
  ColorScheme get colorScheme => Theme.of(this).colorScheme;
  
  double get width => MediaQuery.of(this).size.width;
  double get height => MediaQuery.of(this).size.height;
  
  bool get isDarkMode => Theme.of(this).brightness == Brightness.dark;
  bool get isLightMode => Theme.of(this).brightness == Brightness.light;
  
  void showSnackBar(String message, {bool isError = false}) {
    ScaffoldMessenger.of(this).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: isError ? Colors.red : Colors.green,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }
  
  void showLoading() {
    showDialog(
      context: this,
      barrierDismissible: false,
      builder: (context) => const Center(child: CircularProgressIndicator()),
    );
  }
  
  void hideLoading() {
    Navigator.of(this).pop();
  }
  
  void pop() => Navigator.of(this).pop();
  
  Future<T?> push<T>(Widget page) {
    return Navigator.of(this).push(MaterialPageRoute(builder: (_) => page));
  }
  
  void pushReplacement(Widget page) {
    Navigator.of(this).pushReplacement(MaterialPageRoute(builder: (_) => page));
  }
  
  void pushAndRemoveUntil(Widget page) {
    Navigator.of(this).pushAndRemoveUntil(
      MaterialPageRoute(builder: (_) => page),
      (route) => false,
    );
  }
}