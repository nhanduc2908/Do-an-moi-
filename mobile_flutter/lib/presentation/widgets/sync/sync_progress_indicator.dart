// Đường dẫn: mobile_flutter/lib/presentation/widgets/sync/sync_progress_indicator.dart

import 'package:flutter/material.dart';

class SyncProgressIndicator extends StatelessWidget {
  final double progress;
  final String currentItem;

  const SyncProgressIndicator({
    super.key,
    required this.progress,
    required this.currentItem,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        LinearProgressIndicator(
          value: progress,
          backgroundColor: Colors.grey.shade200,
          color: Colors.blue,
          height: 8,
          borderRadius: BorderRadius.circular(4),
        ),
        const SizedBox(height: 8),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              '${(progress * 100).toInt()}%',
              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
            ),
            Text(
              currentItem,
              style: const TextStyle(fontSize: 12, color: Colors.grey),
            ),
          ],
        ),
      ],
    );
  }
}