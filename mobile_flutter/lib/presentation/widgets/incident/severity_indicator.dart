import 'package:flutter/material.dart';

class SeverityIndicator extends StatelessWidget {
  final String severity;

  const SeverityIndicator({super.key, required this.severity});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: _getColor().withOpacity(0.2),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(_getIcon(), size: 14, color: _getColor()),
          const SizedBox(width: 4),
          Text(
            severity.toUpperCase(),
            style: TextStyle(color: _getColor(), fontSize: 10, fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }

  Color _getColor() {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow.shade700;
      default: return Colors.green;
    }
  }

  IconData _getIcon() {
    switch (severity.toLowerCase()) {
      case 'critical': return Icons.error;
      case 'high': return Icons.warning;
      default: return Icons.info;
    }
  }
}