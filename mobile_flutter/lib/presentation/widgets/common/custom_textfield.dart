import 'package:flutter/material.dart';

class CustomTextField extends StatelessWidget {
  final TextEditingController? controller;
  final String label;
  final String? hintText;
  final String? errorText;
  final IconData? prefixIcon;
  final Widget? suffixIcon;
  final bool obscureText;
  final TextInputType keyboardType;
  final int maxLines;
  final int? maxLength;
  final ValueChanged<String>? onChanged;
  final FocusNode? focusNode;
  final TextAlign textAlign;

  const CustomTextField({
    super.key,
    this.controller,
    required this.label,
    this.hintText,
    this.errorText,
    this.prefixIcon,
    this.suffixIcon,
    this.obscureText = false,
    this.keyboardType = TextInputType.text,
    this.maxLines = 1,
    this.maxLength,
    this.onChanged,
    this.focusNode,
    this.textAlign = TextAlign.start,
  });

  @override
  Widget build(BuildContext context) {
    return TextField(
      controller: controller,
      focusNode: focusNode,
      obscureText: obscureText,
      keyboardType: keyboardType,
      maxLines: maxLines,
      maxLength: maxLength,
      onChanged: onChanged,
      textAlign: textAlign,
      decoration: InputDecoration(
        labelText: label,
        hintText: hintText,
        errorText: errorText,
        prefixIcon: prefixIcon != null ? Icon(prefixIcon) : null,
        suffixIcon: suffixIcon,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
        ),
        counterText: maxLength != null ? null : '',
      ),
    );
  }
}