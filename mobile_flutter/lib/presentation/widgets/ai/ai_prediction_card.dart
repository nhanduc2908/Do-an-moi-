// Đường dẫn: mobile_flutter/lib/presentation/widgets/ai/ai_prediction_card.dart

import 'package:flutter/material.dart';

class AIPredictionCard extends StatelessWidget {
  final String title;
  final String value;
  final double confidence;
  final String trend;
  final VoidCallback onTap;

  const AIPredictionCard({
    super.key,
    required this.title,
    required this.value,
    required this.confidence,
    required this.trend,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    title,
                    style: const TextStyle(fontSize: 14, color: Colors.grey),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.blue.shade100,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      '${(confidence * 100).toInt()}%',
                      style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8),
              Text(
                value,
                style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              Row(
                children: [
                  Icon(
                    trend.startsWith('+') ? Icons.trending_up : Icons.trending_down,
                    size: 16,
                    color: trend.startsWith('+') ? Colors.green : Colors.red,
                  ),
                  const SizedBox(width: 4),
                  Text(
                    trend,
                    style: TextStyle(
                      fontSize: 12,
                      color: trend.startsWith('+') ? Colors.green : Colors.red,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}