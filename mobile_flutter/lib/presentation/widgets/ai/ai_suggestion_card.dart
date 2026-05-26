// Đường dẫn: mobile_flutter/lib/presentation/widgets/ai/ai_suggestion_card.dart

import 'package:flutter/material.dart';

class AISuggestionCard extends StatelessWidget {
  final String title;
  final String description;
  final String impact;
  final VoidCallback onApply;
  final VoidCallback onDismiss;

  const AISuggestionCard({
    super.key,
    required this.title,
    required this.description,
    required this.impact,
    required this.onApply,
    required this.onDismiss,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Icon(Icons.lightbulb, color: Colors.amber),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    title,
                    style: const TextStyle(fontWeight: FontWeight.bold),
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: _getImpactColor(impact).withOpacity(0.2),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Text(
                    impact,
                    style: TextStyle(color: _getImpactColor(impact), fontSize: 10),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(description, style: const TextStyle(fontSize: 13)),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(
                    onPressed: onDismiss,
                    child: const Text('Dismiss'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: onApply,
                    child: const Text('Apply'),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Color _getImpactColor(String impact) {
    switch (impact.toLowerCase()) {
      case 'high': return Colors.red;
      case 'medium': return Colors.orange;
      default: return Colors.blue;
    }
  }
}