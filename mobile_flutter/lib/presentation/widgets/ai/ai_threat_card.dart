import 'package:flutter/material.dart';

class AIThreatCard extends StatelessWidget {
  final String title;
  final String severity;
  final double confidence;
  final VoidCallback onTap;

  const AIThreatCard({
    super.key,
    required this.title,
    required this.severity,
    required this.confidence,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: _getSeverityColor(),
          child: const Icon(Icons.warning, color: Colors.white),
        ),
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text('Confidence: ${(confidence * 100).toInt()}%'),
        trailing: Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
          decoration: BoxDecoration(
            color: _getSeverityColor().withOpacity(0.2),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Text(severity, style: TextStyle(color: _getSeverityColor())),
        ),
        onTap: onTap,
      ),
    );
  }

  Color _getSeverityColor() {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      default: return Colors.yellow.shade700;
    }
  }
}