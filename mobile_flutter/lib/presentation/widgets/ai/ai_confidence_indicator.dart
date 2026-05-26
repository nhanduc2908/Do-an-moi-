// Đường dẫn: mobile_flutter/lib/presentation/widgets/ai/ai_confidence_indicator.dart

import 'package:flutter/material.dart';

class AIConfidenceIndicator extends StatelessWidget {
  final double confidence;

  const AIConfidenceIndicator({super.key, required this.confidence});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text('AI Confidence', style: TextStyle(fontSize: 12)),
            Text(
              '${(confidence * 100).toInt()}%',
              style: TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: _getConfidenceColor(),
              ),
            ),
          ],
        ),
        const SizedBox(height: 4),
        LinearProgressIndicator(
          value: confidence,
          backgroundColor: Colors.grey.shade200,
          color: _getConfidenceColor(),
          height: 6,
          borderRadius: BorderRadius.circular(3),
        ),
      ],
    );
  }

  Color _getConfidenceColor() {
    if (confidence >= 0.8) return Colors.green;
    if (confidence >= 0.6) return Colors.orange;
    return Colors.red;
  }
}