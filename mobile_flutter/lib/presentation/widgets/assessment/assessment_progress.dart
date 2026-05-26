import 'package:flutter/material.dart';

class AssessmentProgress extends StatelessWidget {
  final int current;
  final int total;
  final int progress;

  const AssessmentProgress({
    super.key,
    required this.current,
    required this.total,
    required this.progress,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text('Question $current of $total'),
            Text('$progress% Complete'),
          ],
        ),
        const SizedBox(height: 8),
        LinearProgressIndicator(
          value: progress / 100,
          backgroundColor: Colors.grey.shade200,
        ),
      ],
    );
  }
}