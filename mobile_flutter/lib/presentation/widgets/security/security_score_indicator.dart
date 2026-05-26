// Đường dẫn: mobile_flutter/lib/presentation/widgets/security/security_score_indicator.dart

import 'package:flutter/material.dart';

class SecurityScoreIndicator extends StatelessWidget {
  final double score;

  const SecurityScoreIndicator({super.key, required this.score});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Stack(
          alignment: Alignment.center,
          children: [
            SizedBox(
              height: 100,
              width: 100,
              child: CircularProgressIndicator(
                value: score / 100,
                strokeWidth: 8,
                backgroundColor: Colors.grey.shade200,
                valueColor: AlwaysStoppedAnimation<Color>(_getScoreColor()),
              ),
            ),
            Column(
              children: [
                Text(
                  score.toStringAsFixed(0),
                  style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                ),
                const Text('/100', style: TextStyle(fontSize: 12, color: Colors.grey)),
              ],
            ),
          ],
        ),
        const SizedBox(height: 8),
        Text(
          _getScoreLabel(),
          style: TextStyle(color: _getScoreColor(), fontWeight: FontWeight.bold),
        ),
      ],
    );
  }

  Color _getScoreColor() {
    if (score >= 80) return Colors.green;
    if (score >= 60) return Colors.orange;
    return Colors.red;
  }

  String _getScoreLabel() {
    if (score >= 80) return 'Good';
    if (score >= 60) return 'Fair';
    return 'Poor';
  }
}