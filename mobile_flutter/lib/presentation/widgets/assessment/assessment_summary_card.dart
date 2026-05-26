// Đường dẫn: mobile_flutter/lib/presentation/widgets/assessment/assessment_summary_card.dart

import 'package:flutter/material.dart';

class AssessmentSummaryCard extends StatelessWidget {
  final String title;
  final double score;
  final String status;
  final DateTime date;

  const AssessmentSummaryCard({
    super.key,
    required this.title,
    required this.score,
    required this.status,
    required this.date,
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
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Expanded(
                  child: Text(
                    title,
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: _getStatusColor().withOpacity(0.2),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Text(
                    status,
                    style: TextStyle(color: _getStatusColor(), fontSize: 12),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                const Text('Score:', style: TextStyle(color: Colors.grey)),
                const SizedBox(width: 8),
                Text(
                  score.toStringAsFixed(1),
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: _getScoreColor(),
                  ),
                ),
                const Text('/100'),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              'Date: ${_formatDate(date)}',
              style: const TextStyle(fontSize: 12, color: Colors.grey),
            ),
            const SizedBox(height: 12),
            LinearProgressIndicator(
              value: score / 100,
              backgroundColor: Colors.grey.shade200,
              color: _getScoreColor(),
              height: 6,
            ),
          ],
        ),
      ),
    );
  }

  Color _getStatusColor() {
    switch (status.toLowerCase()) {
      case 'completed': return Colors.green;
      case 'in_progress': return Colors.orange