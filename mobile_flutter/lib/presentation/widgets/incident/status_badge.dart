import 'package:flutter/material.dart';

class StatusBadge extends StatelessWidget {
  final String status;

  const StatusBadge({super.key, required this.status});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: _getColor().withOpacity(0.2),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Text(
        status,
        style: TextStyle(color: _getColor(), fontSize: 12),
      ),
    );
  }

  Color _getColor() {
    switch (status.toLowerCase()) {
      case 'open': return Colors.red;
      case 'investigating': return Colors.orange;
      case 'resolved': return Colors.green;
      default: return Colors.grey;
    }
  }
}