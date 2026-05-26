import 'package:flutter/material.dart';

class VersionBadge extends StatelessWidget {
  final String version;
  final bool isCurrent;

  const VersionBadge({
    super.key,
    required this.version,
    this.isCurrent = false,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: isCurrent ? Colors.green : Colors.grey,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Text(
        version,
        style: const TextStyle(color: Colors.white, fontSize: 10),
      ),
    );
  }
}