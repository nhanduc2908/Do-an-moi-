// Đường dẫn: mobile_flutter/lib/presentation/widgets/sync/last_sync_time.dart

import 'package:flutter/material.dart';

class LastSyncTime extends StatelessWidget {
  final DateTime? lastSyncTime;
  final bool isSyncing;

  const LastSyncTime({
    super.key,
    this.lastSyncTime,
    this.isSyncing = false,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.grey.shade100,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (isSyncing)
            const SizedBox(
              width: 16,
              height: 16,
              child: CircularProgressIndicator(strokeWidth: 2),
            )
          else
            const Icon(Icons.sync, size: 16, color: Colors.grey),
          const SizedBox(width: 8),
          Text(
            isSyncing ? 'Syncing...' : 'Last sync: ${_formatTime(lastSyncTime)}',
            style: const TextStyle(fontSize: 12, color: Colors.grey),
          ),
        ],
      ),
    );
  }

  String _formatTime(DateTime? time) {
    if (time == null) return 'Never';
    final now = DateTime.now();
    final diff = now.difference(time);
    if (diff.inMinutes < 1) return 'Just now';
    if (diff.inMinutes < 60) return '${diff.inMinutes}m ago';
    if (diff.inHours < 24) return '${diff.inHours}h ago';
    return '${diff.inDays}d ago';
  }
}