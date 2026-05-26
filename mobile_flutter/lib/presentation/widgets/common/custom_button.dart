import 'package:flutter/material.dart';

class CustomButton extends StatelessWidget {
  final String text;
  final VoidCallback onPressed;
  final bool isLoading;
  final bool isOutlined;
  final Color? color;
  final double? height;
  final double? width;

  const CustomButton({
    super.key,
    required this.text,
    required this.onPressed,
    this.isLoading = false,
    this.isOutlined = false,
    this.color,
    this.height,
    this.width,
  });

  @override
  Widget build(BuildContext context) {
    final button = isOutlined
        ? OutlinedButton(
            onPressed: isLoading ? null : onPressed,
            style: OutlinedButton.styleFrom(
              minimumSize: Size(width ?? double.infinity, height ?? 48),
              side: BorderSide(color: color ?? Theme.of(context).primaryColor),
            ),
            child: _buildChild(context),
          )
        : ElevatedButton(
            onPressed: isLoading ? null : onPressed,
            style: ElevatedButton.styleFrom(
              backgroundColor: color ?? Theme.of(context).primaryColor,
              minimumSize: Size(width ?? double.infinity, height ?? 48),
            ),
            child: _buildChild(context),
          );

    return button;
  }

  Widget _buildChild(BuildContext context) {
    if (isLoading) {
      return SizedBox(
        height: 20,
        width: 20,
        child: CircularProgressIndicator(
          strokeWidth: 2,
          color: isOutlined ? Theme.of(context).primaryColor : Colors.white,
        ),
      );
    }
    return Text(
      text,
      style: TextStyle(
        fontSize: 16,
        fontWeight: FontWeight.w600,
        color: isOutlined ? Theme.of(context).primaryColor : Colors.white,
      ),
    );
  }
}