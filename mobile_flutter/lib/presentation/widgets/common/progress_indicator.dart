// Đường dẫn: mobile_flutter/lib/presentation/widgets/common/progress_indicator.dart

import 'package:flutter/material.dart';

class CustomProgressIndicator extends StatelessWidget {
  final double value;
  final String? label;
  final Color? color;

  const CustomProgressIndicator({
    super.key,
    required this.value,
    this.label,
    this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        if (label != null) ...[
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(label!, style: const TextStyle(fontSize: 12)),
              Text('${(value * 100).toInt()}%', style: const TextStyle(fontSize: 12)),
            ],
          ),
          const SizedBox(height: 4),
        ],
        LinearProgressIndicator(
          value: value.clamp(0.0, 1.0),
          backgroundColor: Colors.grey.shade200,
          color: color ?? Colors.blue,
          minHeight: 8,
          borderRadius: BorderRadius.circular(4),
        ),
      ],
    );
  }
}