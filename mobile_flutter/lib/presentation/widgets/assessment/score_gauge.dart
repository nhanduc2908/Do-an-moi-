import 'package:flutter/material.dart';

class ScoreGauge extends StatelessWidget {
  final double score;
  final double maxScore;

  const ScoreGauge({
    super.key,
    required this.score,
    this.maxScore = 100,
  });

  @override
  Widget build(BuildContext context) {
    final percentage = (score / maxScore) * 100;
    return Column(
      children: [
        Stack(
          alignment: Alignment.center,
          children: [
            SizedBox(
              height: 100,
              width: 100,
              child: CircularProgressIndicator(
                value: percentage / 100,
                strokeWidth: 8,
                backgroundColor: Colors.grey.shade200,
                valueColor: AlwaysStoppedAnimation<Color>(_getScoreColor(percentage)),
              ),
            ),
            Text(
              '${percentage.toInt()}%',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
          ],
        ),
        const SizedBox(height: 8),
        Text(
          _getScoreLabel(percentage),
          style: TextStyle(color: _getScoreColor(percentage), fontWeight: FontWeight.bold),
        ),
      ],
    );
  }

  Color _getScoreColor(double percentage) {
    if (percentage >= 80) return Colors.green;
    if (percentage >= 60) return Colors.orange;
    return Colors.red;
  }

  String _getScoreLabel(double percentage) {
    if (percentage >= 80) return 'Good';
    if (percentage >= 60) return 'Fair';
    return 'Poor';
  }
}