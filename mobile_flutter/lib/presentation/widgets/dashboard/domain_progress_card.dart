// Đường dẫn: mobile_flutter/lib/presentation/widgets/dashboard/domain_progress_card.dart

import 'package:flutter/material.dart';

class DomainProgressCard extends StatelessWidget {
  final String domainName;
  final int completed;
  final int total;
  final double score;

  const DomainProgressCard({
    super.key,
    required this.domainName,
    required this.completed,
    required this.total,
    required this.score,
  });

  @override
  Widget build(BuildContext context) {
    final progress = total > 0 ? completed / total : 0.0;

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  domainName,
                  style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
                Text(
                  '${score.toInt()}%',
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: _getScoreColor(score),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text('Progress: $completed/$total criteria completed'),
            const SizedBox(height: 8),
            LinearProgressIndicator(
              value: progress,
              backgroundColor: Colors.grey.shade200,
              color: Colors.blue,
              height: 6,
            ),
          ],
        ),
      ),
    );
  }

  Color _getScoreColor(double score) {
    if (score >= 80) return Colors.green;
    if (score >= 60) return Colors.orange;
    return Colors.red;
  }
}