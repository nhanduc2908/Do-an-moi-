// Đường dẫn: mobile_flutter/lib/presentation/widgets/sync/sync_queue_badge.dart

import 'package:flutter/material.dart';

class SyncQueueBadge extends StatelessWidget {
  final int queueSize;

  const SyncQueueBadge({super.key, required this.queueSize});

  @override
  Widget build(BuildContext context) {
    if (queueSize == 0) return const SizedBox.shrink();

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(10),
      ),
      child: Text(
        queueSize > 99 ? '99+' : queueSize.toString(),
        style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
      ),
    );
  }
}