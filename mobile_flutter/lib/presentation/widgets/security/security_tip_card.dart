// Đường dẫn: mobile_flutter/lib/presentation/widgets/security/security_tip_card.dart

import 'package:flutter/material.dart';

class SecurityTipCard extends StatelessWidget {
  final String title;
  final String tip;
  final String category;

  const SecurityTipCard({
    super.key,
    required this.title,
    required this.tip,
    required this.category,
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
                    color: Colors.blue.shade100,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Text(
                    category,
                    style: const TextStyle(fontSize: 10),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(tip, style: const TextStyle(fontSize: 13)),
          ],
        ),
      ),
    );
  }
}