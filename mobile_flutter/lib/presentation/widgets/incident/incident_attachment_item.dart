// Đường dẫn: mobile_flutter/lib/presentation/widgets/incident/incident_attachment_item.dart

import 'package:flutter/material.dart';

class IncidentAttachmentItem extends StatelessWidget {
  final String name;
  final String size;
  final VoidCallback onDownload;
  final VoidCallback onDelete;

  const IncidentAttachmentItem({
    super.key,
    required this.name,
    required this.size,
    required this.onDownload,
    required this.onDelete,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.grey.shade100,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        children: [
          const Icon(Icons.attachment, size: 20, color: Colors.blue),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name, style: const TextStyle(fontWeight: FontWeight.w500)),
                Text(size, style: const TextStyle(fontSize: 12, color: Colors.grey)),
              ],
            ),
          ),
          IconButton(
            icon: const Icon(Icons.download, size: 20),
            onPressed: onDownload,
          ),
          IconButton(
            icon: const Icon(Icons.delete, size: 20, color: Colors.red),
            onPressed: onDelete,
          ),
        ],
      ),
    );
  }
}