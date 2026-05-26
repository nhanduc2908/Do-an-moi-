// Đường dẫn: mobile_flutter/lib/presentation/widgets/common/skeleton_loading.dart

import 'package:flutter/material.dart';

class SkeletonLoading extends StatelessWidget {
  const SkeletonLoading({super.key});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: 5,
      itemBuilder: (context, index) => const SkeletonItem(),
    );
  }
}

class SkeletonItem extends StatelessWidget {
  const SkeletonItem({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 80,
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.grey.shade200,
        borderRadius: BorderRadius.circular(12),
      ),
      child: const Row(
        children: [
          CircleAvatar(backgroundColor: Colors.grey),
          SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Container(height: 12, width: double.infinity, color: Colors.grey),
                SizedBox(height: 8),
                Container(height: 10, width: 100, color: Colors.grey),
              ],
            ),
          ),
        ],
      ),
    );
  }
}